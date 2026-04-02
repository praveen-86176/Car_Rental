import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import React, { useState, useEffect, createContext } from 'react';
import api from './api';
import Navbar from './components/Navbar';
import { Login, RegisterCustomer, RegisterAgency } from './pages/Auth';
import Cars from './pages/Cars';
import { AddCar, Bookings } from './pages/Agency';

export const AuthContext = createContext(null);

export default function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get('/login.php')
      .then(res => {
         if(res.data.success) { setUser(res.data.user); }
      })
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  if(loading) return <div className="container mt-5 text-center">Loading Data...</div>;

  return (
    <AuthContext.Provider value={{ user, setUser }}>
      <Router>
        <Navbar />
        <div className="container py-4">
          <Routes>
            <Route path="/" element={<Cars />} />
            <Route path="/login" element={<Login />} />
            <Route path="/register/customer" element={<RegisterCustomer />} />
            <Route path="/register/agency" element={<RegisterAgency />} />
            <Route path="/add-car" element={<AddCar />} />
            <Route path="/bookings" element={<Bookings />} />
          </Routes>
        </div>
      </Router>
    </AuthContext.Provider>
  );
}
