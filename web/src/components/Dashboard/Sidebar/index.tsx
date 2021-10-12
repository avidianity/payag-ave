import { useToggle, useURL } from '@avidian/hooks';
import React, { FC, useCallback, useEffect } from 'react';
import { useHistory } from 'react-router';
import logo from '../../../assets/logo-rounded.png';
import { routes } from '../../../routes';
import Link from './Link';
import '../../../styles/sidebar.css';
import { sidebarEvents } from '../../../events';

type Props = {};

const Sidebar: FC<Props> = (props) => {
	const url = useURL();
	const history = useHistory();
	const [collapse, setCollapse] = useToggle(false);
	const [mobileOpen, setMobileOpen] = useToggle(false);

	const animate = useCallback(() => {
		setCollapse();
	}, [collapse, setCollapse]);

	useEffect(() => {
		const key = sidebarEvents.listen<boolean>('mobile-sidebar-open', (value) => setMobileOpen(value));

		return () => {
			sidebarEvents.unlisten(key);
		};
	}, []);

	return (
		<div className={`hidden sm:block ${collapse ? 'sidebar-collapse' : ''} ${mobileOpen ? 'sidebar-mobile-open' : ''}`}>
			<div className={`shadow-lg ${!collapse ? 'w-72' : 'w-20'} sidebar h-full border-2 border-gray-100`}>
				<div className='flex mt-4 px-2 items-center'>
					<img
						src={logo}
						alt='Payag Avenue'
						className={`rounded-full ${
							!collapse ? 'ml-1' : 'mx-auto'
						} h-12 w-12 border-2 border-gray-200 shadow-md cursor-pointer`}
						onClick={(e) => {
							e.preventDefault();
							if (collapse) {
								animate();
							} else {
								history.push(routes.DASHBOARD);
							}
						}}
					/>
					{!collapse ? <h4 className='ml-3 text-gray-600'>Payag Ave</h4> : null}
					{!collapse ? (
						<div className='ml-auto mr-2 cursor-pointer hidden sm:inline-block'>
							<i
								className='material-icons '
								onClick={(e) => {
									e.preventDefault();
									animate();
								}}>
								menu
							</i>
						</div>
					) : null}
				</div>
				<div className={`flex flex-col pt-8 px-3 ${collapse ? 'justify-center' : ''}`}>
					<Link title='Categories' to={url(routes.CATEGORIES)} icon='cubes'>
						<span className='sidebar-link-text'>Categories</span>
					</Link>
					<Link title='Products' to={url(routes.PRODUCTS)} icon='shopping-cart'>
						<span className='sidebar-link-text'>Products</span>
					</Link>
					<Link title='Orders' to={url(routes.ORDERS)} icon='shopping-bag'>
						<span className='sidebar-link-text'>Orders</span>
					</Link>
					<Link title='Purchases' to={url(routes.PURCHASES)} icon='credit-card'>
						<span className='sidebar-link-text'>Purchases</span>
					</Link>
					<Link title='Staff' to={url(routes.STAFF)} icon='user-friends'>
						<span className='sidebar-link-text'>Staff</span>
					</Link>
					<Link title='Customers' to={url(routes.CUSTOMERS)} icon='user-friends'>
						<span className='sidebar-link-text'>Customers</span>
					</Link>
				</div>
			</div>
		</div>
	);
};

export default Sidebar;
