import React, { FC } from 'react';

type Props = {};

const Head: FC<Props> = (props) => {
	return <div className='flex items-center mt-8 mb-16'>{props.children}</div>;
};

export default Head;
