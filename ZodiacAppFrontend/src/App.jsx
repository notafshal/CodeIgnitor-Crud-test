import { useEffect, useState } from "react";
import axios from "axios";

const API_URL = "http://localhost:8080/api/fullnames";

const App = () => {
  const [records, setRecords] = useState([]);
  const [formData, setFormData] = useState({ full_name: "", zodiac: "" });

  useEffect(() => {
    fetchRecords();
  }, []);

  const fetchRecords = async () => {
    try {
      const response = await axios.get(API_URL);
      console.log(response);
      setRecords(response.data);
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post(API_URL, formData);
      fetchRecords();
    } catch (error) {
      console.error("Error adding record:", error);
    }
  };

  const handleUpdate = async (id) => {
    try {
      await axios.put(`${API_URL}/${id}`, formData);
      fetchRecords();
    } catch (error) {
      console.error("Error updating record:", error);
    }
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete(`${API_URL}/${id}`);
      fetchRecords();
    } catch (error) {
      console.error("Error deleting record:", error);
    }
  };

  return (
    <div className="container mx-auto p-4 ">
      <h1 className="text-2xl font-bold mb-4">Full Name & Zodiac Table</h1>
      <form onSubmit={handleSubmit} className="mb-4">
        <input
          type="text"
          placeholder="Full Name"
          value={formData.full_name}
          onChange={(e) =>
            setFormData({ ...formData, full_name: e.target.value })
          }
          className="border px-2 py-1 mr-2"
        />
        <input
          type="text"
          placeholder="Zodiac"
          value={formData.zodiac}
          onChange={(e) => setFormData({ ...formData, zodiac: e.target.value })}
          className="border px-2 py-1 mr-2"
        />
        <button type="submit" className="bg-blue-500 text-white px-4 py-1">
          Add
        </button>
      </form>
      <table className="min-w-full bg-white border border-gray-300">
        <thead>
          <tr>
            <th className="border px-4 py-2">ID</th>
            <th className="border px-4 py-2">Full Name</th>
            <th className="border px-4 py-2">Zodiac</th>
            <th className="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {records.map((record) => (
            <tr key={record.id}>
              <td className="border px-4 py-2">{record.id}</td>
              <td className="border px-4 py-2">{record.full_name}</td>
              <td className="border px-4 py-2">{record.zodiac}</td>
              <td className="border px-4 py-2">
                <button
                  onClick={() => handleUpdate(record.id)}
                  className="bg-yellow-500 text-white px-2 py-1 mr-2"
                >
                  Update
                </button>
                <button
                  onClick={() => handleDelete(record.id)}
                  className="bg-red-500 text-white px-2 py-1"
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default App;
