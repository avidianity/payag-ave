import React, { FC, RefAttributes } from 'react';
import { Link as RouterLink, LinkProps } from 'react-router-dom';
import { LocationState } from 'history';

type Props = LinkProps<LocationState> &
	RefAttributes<HTMLAnchorElement> & {
		buttonSize?: 'sm' | 'md' | 'lg' | 'xl';
		color?: 'gray' | 'red' | 'yellow' | 'green' | 'blue' | 'indigo' | 'purple' | 'pink';
	};

const RouterLinkButton: FC<Props> = ({ buttonSize, color, ...props }) => {
	const [h, px] = {
		sm: ['py-1', 'px-3'],
		md: ['py-2', 'px-4'],
		lg: ['py-3', 'px-5'],
		xl: ['py-4', 'px-6'],
	}[buttonSize || 'md'];

	return (
		<RouterLink
			{...props}
			className={`flex items-center ${h} rounded-lg bg-${color || 'blue'}-600 text-white ${px} hover:bg-${color || 'blue'}-700 ${
				props.className || ''
			} disabled:opacity-70`}>
			{props.children}
		</RouterLink>
	);
};

export default RouterLinkButton;
