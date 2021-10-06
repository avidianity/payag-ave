import React, { FC } from 'react';

type Props = {};

const Body: FC<Props> = (props) => {
	return <div className='border-2 rounded-lg border-gray-200 shadow-lg px-4 py-2'>{props.children}</div>;
};

export default Body;
