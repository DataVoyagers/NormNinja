// src/TestAPI.tsx
import { useEffect, useState } from 'react';
import axios from 'axios';

export default function TestAPI() {
  const [message, setMessage] = useState('');

  useEffect(() => {
    axios.get('http://localhost:5000/')
      .then(res => setMessage(res.data))
      .catch(err => console.log(err));
  }, []);

  return <div>{message}</div>;
}
