import React, { FC } from 'react';

type Props = {
	className?: string;
};

const Group: FC<Props> = (props) => {
	return <div className={`w-full px-3 mb-6 md:mb-0 ${props.className || ''}`}>{props.children}</div>;
};

export default Group;
