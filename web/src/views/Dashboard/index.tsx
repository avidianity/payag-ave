import { useURL } from '@avidian/hooks';
import React, { FC, useContext, useEffect } from 'react';
import { Route, RouteProps, Switch, useHistory } from 'react-router-dom';
import Navbar from '../../components/Dashboard/Navbar';
import Sidebar from '../../components/Dashboard/Sidebar';
import { AuthContext } from '../../contexts/auth.context';
import { navbarEvents } from '../../events';
import { useGlobalState } from '../../hooks';
import { UserRoles } from '../../interfaces/user.interface';
import { routes } from '../../routes';
import Categories from './Categories';
import Orders from './Orders';
import Products from './Products';
import Purchases from './Purchases';
import Users from './Users';

type Props = {};

const Dashboard: FC<Props> = (props) => {
	const url = useURL();
	const history = useHistory();
	const state = useGlobalState();
	const { user, setUser, setToken } = useContext(AuthContext);

	const links: RouteProps[] = [
		{
			path: url(routes.CATEGORIES),
			component: Categories,
		},
		{
			path: url(routes.PRODUCTS),
			component: Products,
		},
		{
			path: url(routes.ORDERS),
			component: Orders,
		},
		{
			path: url(routes.PURCHASES),
			component: Purchases,
		},
		{
			path: url(routes.STAFF),
			render: (props) => <Users {...props} roles={[UserRoles.ADMIN, UserRoles.EMPLOYEE]} />,
		},
		{
			path: url(routes.CUSTOMERS),
			render: (props) => <Users {...props} roles={[UserRoles.CUSTOMER]} />,
		},
	];

	useEffect(() => {
		const key = navbarEvents.listen('logout', () => {
			state.clear();
			setUser(null);
			setToken(null);
			history.push(routes.LOGIN);
			toastr.info('Logged out successfully!');
		});

		return () => {
			navbarEvents.unlisten(key);
		};
	}, [navbarEvents, history, state]);

	useEffect(() => {
		if (!user || user.role === UserRoles.CUSTOMER) {
			history.push(routes.LOGIN);
		}
		// eslint-disable-next-line
	}, []);

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
