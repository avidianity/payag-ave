import React, { FC } from 'react';
import Button from '../../../components/Buttons/Button';
import Table from 'react-data-table-component';
import Tooltip from 'react-tooltip';
import { useURL } from '@avidian/hooks';
import { useRebuildTooltip } from '../../../hooks';
import ImageModal from '@avidian/react-modal-image';
import { useQuery } from 'react-query';
import { getCategories } from '../../../queries/category.queries';
import dayjs from 'dayjs';
import Spinner from '../../../components/Spinner';
import RouterLinkButton from '../../../components/Buttons/RouterLinkButton';
import View from '../../../components/Dashboard/View';
import Head from '../../../components/Dashboard/View/Head';
import Body from '../../../components/Card/Body';

type Props = {};

const List: FC<Props> = (props) => {
	const url = useURL();
	useRebuildTooltip();
	const { data, refetch, isFetching } = useQuery('categories', getCategories);

	return (
		<View>
			<Head>
				<h3>Categories</h3>
				<Button
					buttonSize='sm'
					color='indigo'
					className='ml-auto w-20 flex items-center justify-center'
					onClick={(e) => {
						e.preventDefault();
						refetch();
					}}
					disabled={isFetching}>
					{isFetching ? <Spinner /> : 'Refresh'}
				</Button>
				<RouterLinkButton to={url('add')} buttonSize='sm' color='green' className='ml-1'>
					+ Add Category
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
								const url = row.picture?.url || 'https://via.placeholder.com/200';
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
									<Button buttonSize='sm' color='red' className='mx-1 h-8 w-8 flex justify-center' data-tip='Delete'>
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
