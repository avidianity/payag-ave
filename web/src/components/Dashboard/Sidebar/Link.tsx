import React, { RefAttributes, FC, PropsWithoutRef } from 'react';
import { NavLink, NavLinkProps } from 'react-router-dom';

type Props<S = unknown> = PropsWithoutRef<NavLinkProps<S>> &
	RefAttributes<HTMLAnchorElement> & {
		icon: string;
	};

const Link: FC<Props> = ({ icon, children, ...props }) => {
	return (
		<NavLink
			{...props}
			className={`w-full px-2 flex items-center rounded-md py-2 group my-2 ${props.className || ''}`}
			activeClassName='bg-gray-100 navlink-active'>
			<i className={`fas fa-${icon} mr-2 text-gray-500 text-4xl group-hover:text-blue-400 navlink-icon sidebar-link-icon`}></i>
			<span className={`ml-4 text-gray-500 group-hover:text-gray-900 text-lg navlink-text`}>{children}</span>
		</NavLink>
	);
};

export default Link;
