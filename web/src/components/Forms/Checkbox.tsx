import React, { DetailedHTMLProps, forwardRef, InputHTMLAttributes } from 'react';

interface Props extends DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement> {
	label: string;
}

const Checkbox = forwardRef<HTMLInputElement, Props>(({ label, ...props }, ref) => {
	return (
		<label className='group flex relative pl-1 my-1 cursor-pointer select-none items-center'>
			<input ref={ref} {...props} type='checkbox' className='mr-2 focus:outline-none rounded-full focus:bg-transparent' />
			{label}
		</label>
	);
});

export default Checkbox;
