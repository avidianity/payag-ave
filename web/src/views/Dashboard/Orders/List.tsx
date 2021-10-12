import React, { FC } from 'react';
import Button from '../../../components/Buttons/Button';
import Table from 'react-data-table-component';
import Tooltip from 'react-tooltip';
import { useURL } from '@avidian/hooks';
import { useRebuildTooltip } from '../../../hooks';
import { useQuery } from 'react-query';
import { deleteOrder, getOrders } from '../../../queries/order.queries';
import dayjs from 'dayjs';
import Spinner from '../../../components/Spinner';
import RouterLinkButton from '../../../components/Buttons/RouterLinkButton';
import View from '../../../components/Dashboard/View';
import Head from '../../../components/Dashboard/View/Head';
import Body from '../../../components/Card/Body';
import { formatNumber, handleError } from '../../../helpers';
import { OrderInterface } from '../../../interfaces/order.interface';
import swal from 'sweetalert';
import OrderStatusBadge from '../../../components/Badges/OrderStatusBadge';

type Props = {};

const List: FC<Props> = (props) => {
	const url = useURL();
	useRebuildTooltip();
	const { data, refetch, isFetching } = useQuery('orders', getOrders);

	const remove = async (row: OrderInterface) => {
		try {
			if (
				await swal({
					text: 'Are you sure you want to delete this order?',
					icon: 'warning',
					buttons: ['Cancel', 'Confirm'],
					dangerMode: true,
				})
			) {
				await deleteOrder(row.id!);
				toastr.info('Order deleted successfully.');
				await refetch();
			}
		} catch (error) {
			handleError(error);
		}
	};

	return (
		<View>
			<Head>
				<h3>Orders</h3>
				<Button
					buttonSize='sm'
					color='indigo'
					className='ml-auto sm:w-20 flex items-center justify-center'
					onClick={(e) => {
						e.preventDefault();
						refetch();
					}}
					disabled={isFetching}>
					{isFetching ? (
						<Spinner />
					) : (
						<>
							<span className='hidden sm:block'>Refresh</span>
							<span className='block sm:hidden'>
								<i className='fas fa-sync'></i>
							</span>
						</>
					)}
				</Button>
				<RouterLinkButton to={url('add')} buttonSize='sm' color='green' className='ml-1 flex items-center justify-center'>
					<span className='hidden sm:block'>+ Add Order</span>
					<span className='block sm:hidden'>
						<i className='fas fa-plus'></i>
					</span>
				</RouterLinkButton>
			</Head>
			<Body>
				<Table
					pagination
					fixedHeader
					columns={[
						{
							name: 'ID',
							selector: (row) => row.id,
							sortable: true,
						},
						{
							name: 'Paid',
							selector: (row) => formatNumber(row.paid),
							sortable: true,
						},
						{
							name: 'Customer',
							selector: (row) => row.customer?.name || '',
							sortable: true,
						},
						{
							name: 'Biller',
							selector: (row) => row.biller?.name || 'N/A',
							sortable: true,
						},
						{
							name: 'Status',
							cell: (row) => <OrderStatusBadge status={row.status} />,
							sortable: true,
						},
						{
							name: 'Created',
							selector: (row) => dayjs(row.created_at).format('MMMM DD, YYYY hh:mm A'),
							sortable: true,
							minWidth: '225px',
						},
						{
							name: 'Actions',
							cell: (row) => (
								<div className='flex items-center'>
									<RouterLinkButton
										to={url(`${row.id}/view`)}
										buttonSize='sm'
										color='blue'
										className='mx-1 h-8 w-8 flex justify-center'
										data-tip='View'>
										<i className='material-icons' style={{ fontSize: '13px' }}>
											visibility
										</i>
									</RouterLinkButton>
									<RouterLinkButton
										to={url(`${row.id}/edit`)}
										buttonSize='sm'
										color='yellow'
										className='mx-1 h-8 w-8 flex justify-center'
										data-tip='Edit'>
										<i className='material-icons' style={{ fontSize: '13px' }}>
											edit
										</i>
									</RouterLinkButton>
									<Button
										buttonSize='sm'
										color='red'
										className='mx-1 h-8 w-8 flex justify-center'
										data-tip='Delete'
										onClick={(e) => {
											e.preventDefault();
											remove(row);
										}}>
										<i className='material-icons' style={{ fontSize: '13px' }}>
											delete
										</i>
									</Button>
								</div>
							),
						},
					]}
					data={data || []}
					customStyles={{
						rows: {
							style: {
								minHeight: '60px',
							},
						},
					}}
				/>
			</Body>
			<Tooltip />
		</View>
	);
};

export default List;
