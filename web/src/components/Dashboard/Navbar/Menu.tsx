import { useURL } from '@avidian/hooks';
import React, { FC } from 'react';
import { navbarEvents } from '../../../events';
import { routes } from '../../../routes';
import Item from './Item';
import swal from 'sweetalert';

type Props = {};

const Menu: FC<Props> = (props) => {
	const url = useURL();

	return (
		<div className='absolute top-20 right-10 border-2 border-gray-200 bg-white w-40 rounded-md'>
			<ul className='list-none py-1'>
				<Item to={url(routes.PROFILE)} title='Profile' icon='user' activeClassName='text-white bg-blue-400' />
				<Item to={url(routes.SETTINGS)} title='Settings' icon='cog' activeClassName='text-white bg-blue-400' />
				<Item
					to={url(routes.LOGOUT)}
					title='Logout'
					icon='sign-out-alt'
					onClick={async (e) => {
						e.preventDefault();
						if (
							await swal({
								text: 'Are you sure you want to logout?',
								icon: 'warning',
								buttons: ['Cancel', 'Confirm'],
								dangerMode: true,
							})
						) {
							navbarEvents.dispatch('logout', true);
						}
					}}
					activeClassName='text-white bg-blue-400'
				/>
			</ul>
		</div>
	);
};

export default Menu;
