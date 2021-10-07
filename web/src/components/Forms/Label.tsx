import React, { FC } from 'react';

type Props = {
	htmlFor?: string;
	className?: string;
};

const Label: FC<Props> = (props) => {
	return (
		<label htmlFor={props.htmlFor} className='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 mt-3'>
			{props.children}
		</label>
	);
};

export default Label;
