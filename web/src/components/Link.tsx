import React, { FC, RefAttributes } from 'react';
import { Link as RouterLink, LinkProps } from 'react-router-dom';
import { LocationState } from 'history';

type Props = LinkProps<LocationState> & RefAttributes<HTMLAnchorElement> & {};

const Link: FC<Props> = (props) => {
	return (
		<RouterLink {...props} className={`text-blue-500 hover:text-blue-700 active:text-blue-700 px-2 ${props.className || ''}`}>
			{props.children}
		</RouterLink>
	);
};

export default Link;
