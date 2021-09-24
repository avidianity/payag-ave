import { useURL } from '@avidian/hooks';
import React, { FC } from 'react';
import { Route, RouteProps, Switch } from 'react-router-dom';
import List from './List';

type Props = {};

const Categories: FC<Props> = (props) => {
	const url = useURL();

	const links: RouteProps[] = [
		{
			path: url(''),
			component: List,
			exact: true,
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

export default Categories;
