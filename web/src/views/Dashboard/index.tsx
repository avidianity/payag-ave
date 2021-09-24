import { useURL } from '@avidian/hooks';
import React, { FC, useEffect } from 'react';
import { Route, RouteProps, Switch, useHistory } from 'react-router-dom';
import Navbar from '../../components/Dashboard/Navbar';
import Sidebar from '../../components/Dashboard/Sidebar';
import { navbarEvents } from '../../events';
import { useGlobalState } from '../../hooks';
import { routes } from '../../routes';
import Categories from './Categories';

type Props = {};

const Dashboard: FC<Props> = (props) => {
	const url = useURL();
	const history = useHistory();
	const state = useGlobalState();

	const links: RouteProps[] = [
		{
			path: url(routes.CATEGORIES),
			component: Categories,
		},
	];

	useEffect(() => {
		const key = navbarEvents.listen('logout', () => {
			state.clear();
			history.push(routes.LOGIN);
		});

		return () => {
			navbarEvents.unlisten(key);
		};
	}, [navbarEvents, history, state]);

	return (
		<div className='h-full w-full fixed flex'>
			<Sidebar />
			<div className='min-h-screen w-full overflow-y-auto'>
				<Navbar />
				<div className='px-1 pt-3 pb-10'>
					<Switch>
						{links.map((link, index) => (
							<Route {...link} key={index} />
						))}
					</Switch>
				</div>
			</div>
		</div>
	);
};

export default Dashboard;