import React, { FC } from 'react';
import background from '../assets/maintenance-mode.png';

type Props = {};

const MaintenanceMode: FC<Props> = (props) => {
	return (
		<div className='h-screen w-screen flex'>
			<div className='self-center mx-auto text-center pb-10'>
				<img src={background} alt='Maintenance Mode' className='sm:h-72 mx-auto' />
				<h2 className='mt-6 text-gray-700 px-4 sm:px-0'>We are currently performing maintenance</h2>
				<p className='mt-4 text-gray-700'>Please check back after some time</p>
			</div>
		</div>
	);
};

export default MaintenanceMode;
