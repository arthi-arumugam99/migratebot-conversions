import { createClient } from "@supabase/supabase-js"; // MIGRATED: replaced firebase/app and @firebase/firestore imports with Supabase client

// TODO: Set REACT_APP_SUPABASE_URL and REACT_APP_SUPABASE_ANON_KEY in your .env file
// (Create React App uses REACT_APP_ prefix instead of NEXT_PUBLIC_)
// Example .env entries:
//   REACT_APP_SUPABASE_URL=https://your-project-ref.supabase.co
//   REACT_APP_SUPABASE_ANON_KEY=your-anon-key-here

// MIGRATED: removed firebaseConfig object, initializeApp(firebaseConfig), and getFirestore(app)
const supabaseUrl = process.env.REACT_APP_SUPABASE_URL;
const supabaseAnonKey = process.env.REACT_APP_SUPABASE_ANON_KEY;

export const supabase = createClient(supabaseUrl, supabaseAnonKey); // MIGRATED: single Supabase client replaces app + db exports

// MIGRATED: 'db' alias exported for compatibility with existing imports of 'db' throughout the app
export const db = supabase;