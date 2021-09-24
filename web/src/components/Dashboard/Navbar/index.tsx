import { useToggle } from '@avidian/hooks';
import React, { FC, useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { navbarEvents, sidebarEvents } from '../../../events';
import Input from '../../Forms/Input';
import Menu from './Menu';

type Props = {};

type SearchInputs = {
	query: string;
};

const Navbar: FC<Props> = (props) => {
	const { register, handleSubmit } = useForm<SearchInputs>();
	const [sidebarOpen, setSidebarOpen] = useToggle(false);
	const [menuOpen, setMenuOpen] = useToggle(false);

	const submit = async (data: SearchInputs) => {
		navbarEvents.dispatch('search', data.query);
	};

	useEffect(() => {
		sidebarEvents.dispatch('mobile-sidebar-open', sidebarOpen);
		if (sidebarOpen) {
			setMenuOpen(false);
		}
	}, [sidebarOpen, setMenuOpen]);

	return (
		<div className={`w-full shadow-md border-b-2 border-gray-100 flex items-center py-3 sm:py-1`}>
			<span className='ml-4 block sm:hidden pt-2'>
				<i
					className='material-icons cursor-pointer'
					onClick={(e) => {
						e.preventDefault();
						setSidebarOpen();
					}}>
					menu
				</i>
			</span>
			<form className='w-64 ml-4 hidden sm:block' onSubmit={handleSubmit(submit)}>
				<Input {...register('query')} inputSize='sm' type='search' id='search' placeholder='Search Here' />
			</form>
			<button
				type='button'
				className='bg-none rounded-full border-0 ml-auto mr-8 group flex items-center pt-1'
				onClick={(e) => {
					e.preventDefault();
					if (!sidebarOpen) {
						setMenuOpen();
					} else {
						setSidebarOpen(false);
						setMenuOpen();
					}
				}}>
				<i className='fas fa-user-circle fa-2x group-hover:text-gray-800 text-gray-500'></i>
				<span className='mb-1 ml-1 hidden sm:inline group-hover:text-gray-800 text-gray-500'>John Doe</span>
			</button>
			{menuOpen ? <Menu /> : null}
		</div>
	);
};

export default Navbar;
