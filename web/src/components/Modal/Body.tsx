import React, { FC } from 'react';

type Props = {};

const Body: FC<Props> = (props) => {
	return <div className='px-8 pt-6 border-t max-h-150 overflow-y-auto overflow-x-hidden'>{props.children}</div>;
};

export default Body;
