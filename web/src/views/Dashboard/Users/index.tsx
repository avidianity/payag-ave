import { useURL } from '@avidian/hooks';
import React, { FC } from 'react';
import { Route, RouteProps, Switch } from 'react-router-dom';
import { UserRoles } from '../../../interfaces/user.interface';
import Form from './Form';
import List from './List';

type Props = {
	roles: UserRoles[];
};

const Users: FC<Props> = ({ roles }) => {
	const url = useURL();

	const links: RouteProps[] = [
		{
			path: url(''),
			render: (props) => <List {...props} roles={roles} />,
			exact: true,
		},
		{
			path: url('add'),
			render: (props) => <Form {...props} roles={roles} />,
		},
		{
			path: url(':id/edit'),
			render: (props) => <Form {...props} roles={roles} />,
		},
	];

	return (
		<Switch>
			{links.map((link, index) => (
				<Route {...link} key={index} />
			))}
		</Switch>
	);
};

export default Users;
