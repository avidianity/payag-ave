import React from 'react';
import { BrowserRouter, HashRouter, Route, RouteProps, Switch } from 'react-router-dom';
import { routes } from './routes';
import Dashboard from './views/Dashboard';
import Login from './views/Login';
import Register from './views/Register';

const Router: any = import.meta.env.VITE_DESKTOP === 'true' ? HashRouter : BrowserRouter;

export default function App() {
	const links: RouteProps[] = [
		{
			path: routes.HOME,
			component: Login,
			exact: true,
		},
		{
			path: routes.LOGIN,
			component: Login,
		},
		{
			path: routes.REGISTER,
			component: Register,
		},
		{
			path: routes.DASHBOARD,
			component: Dashboard,
		},
	];

	return (
		<Router>
			<Switch>
				{links.map((link, index) => (
					<Route {...link} key={index} />
				))}
			</Switch>
		</Router>
	);
}
