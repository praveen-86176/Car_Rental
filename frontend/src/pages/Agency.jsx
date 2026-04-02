import { useState, useEffect } from 'react';
import api from '../api';

export function AddCar() {
  const [form, setForm] = useState({ model: '', vehicle_number: '', seating_capacity: '', rent_per_day: '' });
  
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('/add_car.php', form);
      alert('Car added successfully!');
      setForm({ model: '', vehicle_number: '', seating_capacity: '', rent_per_day: '' });
    } catch (err) {
      alert(err.response?.data?.error || "Error adding car");
    }
  };

  return (
    <div className="row justify-content-center"><div className="col-md-6"><div className="card p-4">
      <h3 className="mb-4 text-center">Add New Car</h3>
      <form onSubmit={handleSubmit}>
        <div className="mb-3"><label>Vehicle Model</label><input type="text" required className="form-control" value={form.model} onChange={e => setForm({...form, model: e.target.value})} /></div>
        <div className="mb-3"><label>Vehicle Number</label><input type="text" required className="form-control" value={form.vehicle_number} onChange={e => setForm({...form, vehicle_number: e.target.value})} /></div>
        <div className="mb-3"><label>Seating Capacity</label><input type="number" min="1" required className="form-control" value={form.seating_capacity} onChange={e => setForm({...form, seating_capacity: e.target.value})} /></div>
        <div className="mb-3"><label>Rent Per Day ($)</label><input type="number" step="0.01" min="1" required className="form-control" value={form.rent_per_day} onChange={e => setForm({...form, rent_per_day: e.target.value})} /></div>
        <button type="submit" className="btn btn-primary w-100">Publish Car</button>
      </form>
    </div></div></div>
  );
}

export function Bookings() {
  const [bookings, setBookings] = useState([]);

  useEffect(() => {
    api.get('/bookings.php').then(res => setBookings(res.data.bookings)).catch(()=>{});
  }, []);

  return (
    <div>
      <h3 className="mb-4">View Booked Cars</h3>
      <div className="table-responsive"><table className="table table-striped align-middle table-hover">
        <thead className="table-dark"><tr><th>Customer</th><th>Phone</th><th>Car Model</th><th>Vehicle No</th><th>Date</th><th>Days</th><th>Cost</th></tr></thead>
        <tbody>
          {bookings.map(b => (
            <tr key={b.booking_id}>
                <td>{b.customer_name}</td><td>{b.customer_phone}</td><td>{b.car_model}</td><td>{b.vehicle_number}</td><td>{b.start_date}</td><td>{b.num_days}</td><td>${parseFloat(b.total_cost).toFixed(2)}</td>
            </tr>
          ))}
        </tbody>
      </table></div>
    </div>
  );
}
