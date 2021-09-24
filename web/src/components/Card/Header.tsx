import React, { FC } from 'react';
import Container from './Container';

type Props = {
	title: string;
	description?: string;
	picture?: string;
};

const Header: FC<Props> = ({ title, description, picture }) => {
	return (
		<Container>
			{picture ? (
				<div className='flex justify-center items-center'>
					<img src={picture} alt='Card Header' className='h-40 w-40 rounded-full shadow-lg border-2 border-gray-100' />
				</div>
			) : null}
			<h2>{title}</h2>
			{description ? <p className='text-gray-500 mt-5 text-lg'>{description}</p> : null}
		</Container>
	);
};

export default Header;
