import React, { AnchorHTMLAttributes, DetailedHTMLProps, FC } from 'react';

interface Props extends DetailedHTMLProps<AnchorHTMLAttributes<HTMLAnchorElement>, HTMLAnchorElement> {}

const BaseLink: FC<Props> = (props) => {
	return (
		<a {...props} className={`text-blue-500 hover:text-blue-700 active:text-blue-700 px-2 ${props.className || ''}`}>
			{props.children}
		</a>
	);
};

export default BaseLink;
