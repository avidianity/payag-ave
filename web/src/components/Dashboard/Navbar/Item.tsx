import React, { FC, PropsWithoutRef, RefAttributes } from 'react';
import { NavLink as Link, NavLinkProps } from 'react-router-dom';

type Props<S = unknown> = PropsWithoutRef<NavLinkProps<S>> &
	RefAttributes<HTMLAnchorElement> & {
		title: string;
		icon: string;
	};

const Item: FC<Props> = ({ title, icon, ...props }) => {
	return (
		<li className='my-1 mx-2 rounded-md border-2 border-gray-200 group hover:bg-blue-400'>
			<Link {...props} className={`p-1 mx-0 rounded-md group-hover:text-white block w-full ${props.className || ''}`} title={title}>
				<i className={`fas fa-${icon} ml-1 mr-2`}></i>
				{title}
			</Link>
		</li>
	);
};

export default Item;
