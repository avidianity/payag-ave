import React, { FC } from 'react';

type Props = {};

const CenterFlexContainer: FC<Props> = ({ children }) => {
	return <div className='py-20 md:py-0 md:h-screen flex items-center justify-center w-full'>{children}</div>;
};

export default CenterFlexContainer;
