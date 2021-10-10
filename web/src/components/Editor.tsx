import React, { FC } from 'react';
import ReactQuill from 'react-quill';
import 'react-quill/dist/quill.snow.css';

type Props = {
	onChange: (value: string) => void;
	value: string;
};

const Editor: FC<Props> = ({ onChange, value }) => {
	return <ReactQuill theme='snow' onChange={onChange} value={value} />;
};

export default Editor;
