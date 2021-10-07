import React, { DetailedHTMLProps, InputHTMLAttributes, forwardRef } from 'react';

interface Props extends DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement> {
	inputSize?: 'sm' | 'md';
	onUpload?: (files: File[]) => void;
}

const HiddenFile = forwardRef<HTMLInputElement, Props>(({ inputSize, onUpload, ...props }, ref) => {
	const h = {
		sm: 'h-10',
		md: 'h-12',
	}[inputSize || 'md'];

	return (
		<input
			{...props}
			ref={ref}
			className='hidden'
			type='file'
			onChange={(e) => {
				if (e.target.files && e.target.files.length > 0 && onUpload) {
					onUpload(Array.from(e.target.files));
				}
			}}
		/>
	);
});

export default HiddenFile;
