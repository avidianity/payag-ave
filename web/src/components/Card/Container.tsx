import React, { DetailedHTMLProps, FC, HTMLAttributes } from 'react';

interface Props extends DetailedHTMLProps<HTMLAttributes<HTMLDivElement>, HTMLDivElement> {}

const Container: FC<Props> = (props) => {
	return (
		<div {...props} className='px-5 my-2'>
			{props.children}
		</div>
	);
};

export default Container;
