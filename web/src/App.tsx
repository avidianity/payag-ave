import { useNullable, useToggle } from '@avidian/hooks';
import axios, { AxiosError } from 'axios';
import React, { useEffect } from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { BrowserRouter, HashRouter, Route, RouteProps, Switch } from 'react-router-dom';
import Loading from './components/Loading';
import MaintenanceMode from './components/MaintenanceMode';
import { AuthContext } from './contexts/auth.context';
import { route } from './helpers';
import { useGlobalState } from './hooks';
import { UserInterface } from './interfaces/user.interface';
import { routes } from './routes';
import Dashboard from './views/Dashboard';
import Login from './views/Login';
import Register from './views/Register';

const Router: any = import.meta.env.VITE_DESKTOP === 'true' ? HashRouter : BrowserRouter;

export default function App() {
	const state = useGlobalState();
	const [loading, setLoading] = useToggle(true);
	const [isMaintenanceMode, setIsMaintenanceMode] = useToggle(false);
	const [user, setUser] = useNullable<UserInterface>(state.get('user'));
	const [token, setToken] = useNullable<string>(state.get('token'));

	const links: RouteProps[] = [
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
			if (state.has('token')) {
				const token = state.get<string>('token');
				const {
					data: { data },
				} = await axios.get<{ data: UserInterface }>(route('v1.auth.check'));
				setUser(data);
				setToken(token);
			}
		} catch (error: any) {
			if (error.response?.status) {
				switch (error.response.status) {
					case 401:
						setUser(null);
						setToken(null);
						break;
					case 503:
						return setIsMaintenanceMode(true);
				}
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
		<QueryClientProvider client={new QueryClient()}>
			<AuthContext.Provider value={{ setToken, setUser, token, user }}>
				<Router>
					<Switch>
						{links.map((link, index) => (
							<Route {...link} key={index} />
						))}
					</Switch>
				</Router>
			</AuthContext.Provider>
		</QueryClientProvider>
	);
}
