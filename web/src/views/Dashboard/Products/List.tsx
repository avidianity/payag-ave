import React, { FC, useContext } from 'react';
import Button from '../../../components/Buttons/Button';
import Table from 'react-data-table-component';
import Tooltip from 'react-tooltip';
import { useURL } from '@avidian/hooks';
import { useRebuildTooltip } from '../../../hooks';
import ImageModal from '@avidian/react-modal-image';
import { useQuery } from 'react-query';
import { deleteProduct, getProducts } from '../../../queries/product.queries';
import dayjs from 'dayjs';
import Spinner from '../../../components/Spinner';
import RouterLinkButton from '../../../components/Buttons/RouterLinkButton';
import View from '../../../components/Dashboard/View';
import Head from '../../../components/Dashboard/View/Head';
import Body from '../../../components/Card/Body';
import { AuthContext } from '../../../contexts/auth.context';
import { handleError, urlWithToken } from '../../../helpers';
import { ProductInterface } from '../../../interfaces/product.interface';
import swal from 'sweetalert';

type Props = {};

const List: FC<Props> = (props) => {
	const url = useURL();
	useRebuildTooltip();
	const { data, refetch, isFetching } = useQuery('products', getProducts);
	const { token } = useContext(AuthContext);

	const remove = async (row: ProductInterface) => {
		try {
			if (
				await swal({
					text: 'Are you sure you want to delete this product?',
					icon: 'warning',
					buttons: ['Cancel', 'Confirm'],
					dangerMode: true,
				})
			) {
				await deleteProduct(row.id!);
				toastr.info('Product deleted successfully.');
				await refetch();
			}
		} catch (error) {
			handleError(error);
		}
	};

	return (
		<View>
			<Head>
				<h3>Products</h3>
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
					<span className='hidden sm:block'>+ Add Product</span>
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
							name: 'Image',
							cell: (row) => {
								const url = row.picture?.url ? urlWithToken(row.picture.url, token!) : 'https://via.placeholder.com/200';
								return (
									<ImageModal
										small={url}
										medium={url}
										large={url}
										alt={row.name}
										className='rounded-md shadow-sm h-10 w-10'
									/>
								);
							},
						},
						{
							name: 'Name',
							selector: (row) => row.name,
							sortable: true,
						},
						{
							name: 'Code',
							selector: (row) => row.code,
							sortable: true,
						},
						{
							name: 'Category',
							selector: (row) => row.category?.name,
							sortable: true,
						},
						{
							name: 'Price',
							selector: (row) => row.price,
							sortable: true,
						},
						{
							name: 'Cost',
							selector: (row) => row.cost,
							sortable: true,
						},
						{
							name: 'Quantity',
							selector: (row) => row.quantity,
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
