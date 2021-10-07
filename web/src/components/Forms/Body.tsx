import React, { FC } from 'react';

type Props = {};

const Body: FC<Props> = (props) => {
	return <div className='flex flex-wrap -mx-3 mb-6'>{props.children}</div>;
};

export default Body;
