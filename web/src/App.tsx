import { useToggle } from '@avidian/hooks';
import axios, { AxiosError } from 'axios';
import React, { useEffect } from 'react';
import { BrowserRouter, HashRouter, Route, RouteProps, Switch } from 'react-router-dom';
import Loading from './components/Loading';
import MaintenanceMode from './components/MaintenanceMode';
import { routes } from './routes';
import Dashboard from './views/Dashboard';
import Login from './views/Login';
import Register from './views/Register';

const Router: any = import.meta.env.VITE_DESKTOP === 'true' ? HashRouter : BrowserRouter;

export default function App() {
	const [loading, setLoading] = useToggle(true);
	const [isMaintenanceMode, setIsMaintenanceMode] = useToggle(false);

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

	const check = async () => {
		if (!loading) {
			setLoading(true);
		}
		try {
			await axios.get('/sanctum/csrf-cookie');
		} catch (error: any) {
			if (error.response?.status === 503) {
				setIsMaintenanceMode(true);
			}
		} finally {
			setLoading(false);
		}
	};

	useEffect(() => {
		check();
	}, []);

	if (loading) {
		return <Loading />;
	}

	if (isMaintenanceMode) {
		return <MaintenanceMode />;
	}

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
