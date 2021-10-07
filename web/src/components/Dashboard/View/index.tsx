import React, { FC } from 'react';

type Props = {};

const View: FC<Props> = (props) => {
	return <div className='px-4 sm:px-10 pt-8'>{props.children}</div>;
};

export default View;
