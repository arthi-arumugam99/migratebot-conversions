import { useState, useEffect } from "react";
import "./App.css";
import { supabase } from "./supabase-config"; // MIGRATED: replaced firebase-config import with supabase client

// MIGRATED: removed firebase/firestore imports (collection, getDocs, addDoc, updateDoc, deleteDoc, doc)
// TODO: Add @supabase/supabase-js to package.json and remove firebase package

function App() {
  const [newName, setNewName] = useState("");
  const [newAge, setNewAge] = useState(0);

  const [users, setUsers] = useState([]);
  // MIGRATED: removed usersCollectionRef — Supabase uses table name string directly in queries

  const createUser = async () => {
    // MIGRATED: addDoc(usersCollectionRef, { name, age }) → supabase.from('users').insert(data)
    const { error } = await supabase
      .from("users")
      .insert({ name: newName, age: Number(newAge) });
    // TODO: Handle error case (e.g., show error message to user)
    if (error) console.error("Error creating user:", error);
  };

  const updateUser = async (id, age) => {
    // MIGRATED: updateDoc(doc(db, 'users', id), newFields) → supabase.from('users').update(newFields).eq('id', id)
    const { error } = await supabase
      .from("users")
      .update({ age: age + 1 })
      .eq("id", id);
    // TODO: Handle error case (e.g., show error message to user)
    if (error) console.error("Error updating user:", error);
  };

  const deleteUser = async (id) => {
    // MIGRATED: deleteDoc(doc(db, 'users', id)) → supabase.from('users').delete().eq('id', id)
    const { error } = await supabase
      .from("users")
      .delete()
      .eq("id", id);
    // TODO: Handle error case (e.g., show error message to user)
    if (error) console.error("Error deleting user:", error);
  };

  useEffect(() => {
    const getUsers = async () => {
      // MIGRATED: getDocs(usersCollectionRef) → supabase.from('users').select('*')
      // MIGRATED: replaced data.docs.map((doc) => ({ ...doc.data(), id: doc.id })) with direct data array
      const { data, error } = await supabase.from("users").select("*");
      // TODO: Handle error case (e.g., show error message to user)
      if (error) {
        console.error("Error fetching users:", error);
        return;
      }
      setUsers(data);
    };

    getUsers();
  }, []);

  // TODO: CRITICAL — Enable Row Level Security on the 'users' table in Supabase:
  //   ALTER TABLE users ENABLE ROW LEVEL SECURITY;
  //   Then create appropriate RLS policies in the Supabase Dashboard or via SQL.
  // TODO: Ensure the 'users' table exists in Supabase with columns: id (uuid or serial), name (text), age (integer)
  // TODO: Set REACT_APP_SUPABASE_URL and REACT_APP_SUPABASE_ANON_KEY in your .env file
  //   and create src/supabase-config.js exporting a supabase client via createClient(url, anonKey)

  return (
    <div className="App">
      <input
        placeholder="Name..."
        onChange={(event) => {
          setNewName(event.target.value);
        }}
      />
      <input
        type="number"
        placeholder="Age..."
        onChange={(event) => {
          setNewAge(event.target.value);
        }}
      />

      <button onClick={createUser}> Create User</button>
      {users.map((user) => {
        return (
          <div>
            {" "}
            <h1>Name: {user.name}</h1>
            <h1>Age: {user.age}</h1>
            <button
              onClick={() => {
                updateUser(user.id, user.age);
              }}
            >
              {" "}
              Increase Age
            </button>
            <button
              onClick={() => {
                deleteUser(user.id);
              }}
            >
              {" "}
              Delete User
            </button>
          </div>
        );
      })}
    </div>
  );
}

export default App;