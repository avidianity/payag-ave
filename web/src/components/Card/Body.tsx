import React, { DetailedHTMLProps, FC, HTMLAttributes } from 'react';
import Container from './Container';

interface Props extends DetailedHTMLProps<HTMLAttributes<HTMLDivElement>, HTMLDivElement> {}

const Body: FC<Props> = (props) => {
	return <Container {...props}>{props.children}</Container>;
};

export default Body;
