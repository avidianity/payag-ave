import { useURL } from '@avidian/hooks';
import React, { FC } from 'react';
import { Route, RouteProps, Switch } from 'react-router-dom';
import Form from './Form';
import List from './List';

type Props = {};

const Products: FC<Props> = (props) => {
	const url = useURL();

	const links: RouteProps[] = [
		{
			path: url(''),
			component: List,
			exact: true,
		},
		{
			path: url('add'),
			component: Form,
		},
		{
			path: url(':id/edit'),
			component: Form,
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

export default Products;
