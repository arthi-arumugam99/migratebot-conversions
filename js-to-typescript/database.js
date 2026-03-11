import sqlite3Module from 'sqlite3'
// TODO: Install @types/md5 or create declarations
import md5 from 'md5'

const sqlite3 = sqlite3Module.verbose()

const DBSOURCE: string = "db.sqlite"

const db: sqlite3Module.Database = new sqlite3.Database(DBSOURCE, (err: Error | null) => {
    if (err) {
      // Cannot open database
      console.error(err.message)
      throw err
    } else {
        console.log('Connected to the SQlite database.')
        db.run(`CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name text, 
            email text UNIQUE, 
            password text, 
            CONSTRAINT email_unique UNIQUE (email)
            )`, (err: Error | null) => {
        if (err) {
            // Table already created
        } else {
            // Table just created, creating some rows
            var insert = 'INSERT INTO user (name, email, password) VALUES (?,?,?)'
            db.run(insert, ["admin", "admin@example.com", md5("admin123456")])
            db.run(insert, ["user", "user@example.com", md5("user123456")])
        }
    })
    }
})

export default db