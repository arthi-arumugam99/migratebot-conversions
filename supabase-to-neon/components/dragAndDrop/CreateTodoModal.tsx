import React, { useState } from 'react';
import { Button, Dialog, DialogTitle, DialogContent, DialogActions, TextField, IconButton } from '@mui/material';
import CloseIcon from '@mui/icons-material/Close';
import { useAuth } from '@clerk/nextjs';
import toast from 'react-hot-toast';
import { neon } from '@neondatabase/serverless'; // MIGRATED: Supabase client → Neon serverless driver
import { drizzle } from 'drizzle-orm/neon-http'; // MIGRATED: Supabase client → Drizzle ORM
// TODO: Define Drizzle schema for table definitions (see drizzle-orm docs)
// TODO: Import your todos table schema, e.g.: import { todos } from '@/db/schema';

type NewTodoProps = {
    open: boolean;
    handleClose: () => void;
    onActionFinish: () => Promise<void>;
};

const CreateTodoModal = (props: NewTodoProps) => {
    const { open, handleClose, onActionFinish = () => {} } = props;
    const [input, setInput] = useState<string>('');

    // MIGRATED: Supabase client removed — using Drizzle ORM with Neon serverless driver
    const sql = neon(process.env.DATABASE_URL!);
    const db = drizzle(sql);

    const { userId } = useAuth();

    const createTodo = async () => {
        const currentTime = new Date().getTime();

        // MIGRATED: Supabase query builder → Drizzle ORM
        // TODO: Manual review needed — verify Drizzle schema matches Supabase table (todos)
        try {
            await db.insert(todos).values({ // TODO: Replace `todos` with your imported Drizzle schema table
                text: input,
                clerk_user_id: userId,
                todo_level: 0,
                created_at: currentTime,
                updated_at: currentTime,
            });
        } catch (error: any) {
            console.log('create todo error', error.message);
        }

        setInput('');
        handleClose();
        toast.success('Todo created successfully.');
        onActionFinish();
    };

    return (
        <Dialog
            open={open}
            onClose={handleClose}
            aria-describedby="alert-dialog-slide-description"
            fullWidth
        >
            <DialogTitle>Add New Card</DialogTitle>
            <IconButton
                aria-label="close"
                onClick={handleClose}
                sx={{
                    position: 'absolute',
                    right: 8,
                    top: 8,
                    color: (theme) => theme.palette.grey[500],
                }}
            >
                <CloseIcon />
            </IconButton>
            <DialogContent>
                <TextField
                    size="small"
                    fullWidth
                    id="outlined-basic"
                    label="Title"
                    variant="outlined"
                    value={input}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => { setInput(event.target.value); }}
                />
            </DialogContent>
            <DialogActions>
                <Button variant="outlined" color="error" onClick={handleClose}>Cancel</Button>
                <Button variant="outlined" onClick={() => { createTodo(); }}>Save</Button>
            </DialogActions>
        </Dialog>
    );
};

export default CreateTodoModal;