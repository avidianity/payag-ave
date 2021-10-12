import React, { FC, useContext } from 'react';
import Button from '../../../components/Buttons/Button';
import Table from 'react-data-table-component';
import Tooltip from 'react-tooltip';
import { useURL } from '@avidian/hooks';
import { useRebuildTooltip } from '../../../hooks';
import { useQuery } from 'react-query';
import { deleteUser, getUsers } from '../../../queries/user.queries';
import dayjs from 'dayjs';
import Spinner from '../../../components/Spinner';
import RouterLinkButton from '../../../components/Buttons/RouterLinkButton';
import View from '../../../components/Dashboard/View';
import Head from '../../../components/Dashboard/View/Head';
import Body from '../../../components/Card/Body';
import { AuthContext } from '../../../contexts/auth.context';
import { handleError, urlWithToken } from '../../../helpers';
import { UserInterface, UserRoles } from '../../../interfaces/user.interface';
import swal from 'sweetalert';
import ImageModal from '@avidian/react-modal-image';
import { upperFirst } from 'lodash';
import SuccessBadge from '../../../components/Badges/SuccessBadge';
import DangerBadge from '../../../components/Badges/DangerBadge';

type Props = {
	roles: UserRoles[];
};

const List: FC<Props> = ({ roles }) => {
	const url = useURL();
	useRebuildTooltip();
	const { data, refetch, isFetching } = useQuery(roles.join('-'), () => getUsers({ roles }));
	const { token, user } = useContext(AuthContext);

	const remove = async (row: UserInterface) => {
		try {
			if (
				await swal({
					text: 'Are you sure you want to delete this user?',
					icon: 'warning',
					buttons: ['Cancel', 'Confirm'],
					dangerMode: true,
				})
			) {
				await deleteUser(row.id!);
				toastr.info('User deleted successfully.');
				await refetch();
			}
		} catch (error) {
			handleError(error);
		}
	};

	return (
		<View>
			<Head>
				<h3>{roles.map((role) => upperFirst(role)).join(' - ')}</h3>
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
					<span className='hidden sm:block'>+ Add User</span>
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
							name: 'Email',
							selector: (row) => row.email,
							sortable: true,
						},
						{
							name: 'Role',
							selector: (row) => upperFirst(row.role),
							sortable: true,
						},
						{
							name: 'Phone',
							selector: (row) => row.phone,
							sortable: true,
						},
						{
							name: 'Status',
							cell: (row) => (row.status ? <SuccessBadge>Active</SuccessBadge> : <DangerBadge>Inactive</DangerBadge>),
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
							minWidth: '150px',
							cell: (row) =>
								user?.id !== row.id ? (
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
								) : null,
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
