import React, { FC, useEffect } from 'react';
import Button from '../../../components/Button';
import Table from 'react-data-table-component';
import Tooltip from 'react-tooltip';
import { useURL } from '@avidian/hooks';
import { useHistory } from 'react-router';
import { useRebuildTooltip } from '../../../hooks';
import ImageModal from '@avidian/react-modal-image';

type Props = {};

const List: FC<Props> = (props) => {
	const url = useURL();
	const history = useHistory();
	useRebuildTooltip();

	const data = Array.from(Array(50).keys()).map(() => {
		return {
			id: String.random(40),
			image: 'https://via.placeholder.com/200',
			name: String.random(10),
			code: String.random(5).toUpperCase(),
		};
	});

	return (
		<div className='px-4 sm:px-10 pt-8'>
			<div className='flex items-center mt-8 mb-16'>
				<h3>Categories</h3>
				<Button
					buttonSize='sm'
					color='green'
					className='ml-auto'
					onClick={(e) => {
						e.preventDefault();
						history.push(url('/add'));
					}}>
					+ Add Category
				</Button>
			</div>
			<div className='border-2 rounded-lg border-gray-200 shadow-lg px-4 py-2'>
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
							cell: (row) => (
								<ImageModal
									small={row.image}
									medium={row.image}
									large={row.image}
									alt={row.name}
									className='rounded-md shadow-sm h-10 w-10'
								/>
							),
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
							name: 'Actions',
							cell: (row) => (
								<div className='flex items-center'>
									<Button buttonSize='sm' color='blue' className='mx-1 h-8 w-8 flex justify-center' data-tip='View'>
										<i className='material-icons' style={{ fontSize: '13px' }}>
											visibility
										</i>
									</Button>
									<Button buttonSize='sm' color='yellow' className='mx-1 h-8 w-8 flex justify-center' data-tip='Edit'>
										<i className='material-icons' style={{ fontSize: '13px' }}>
											edit
										</i>
									</Button>
									<Button buttonSize='sm' color='red' className='mx-1 h-8 w-8 flex justify-center' data-tip='Delete'>
										<i className='material-icons' style={{ fontSize: '13px' }}>
											delete
										</i>
									</Button>
								</div>
							),
						},
					]}
					data={data}
					customStyles={{
						rows: {
							style: {
								minHeight: '60px',
							},
						},
					}}
				/>
			</div>
			<Tooltip />
		</div>
	);
};

export default List;
