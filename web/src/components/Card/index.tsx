import React, { FC } from 'react';

type Props = {};

const Card: FC<Props> = ({ children }) => {
	return <div className='rounded-md py-6 px-3 border-2 border-gray-300 w-11/12 sm:w-5/6 md:w-3/6 lg:w-2/5 xl:w-3/12'>{children}</div>;
};

export default Card;
