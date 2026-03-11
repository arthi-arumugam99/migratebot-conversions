import React, { useEffect, useState } from 'react';
import { Button, Dialog, DialogTitle, DialogContent, DialogActions, TextField, IconButton } from '@mui/material';
import CloseIcon from '@mui/icons-material/Close';
import { useAuth } from '@clerk/nextjs';
import toast from 'react-hot-toast';
import { neon } from '@neondatabase/serverless'; // MIGRATED: Supabase client → Neon serverless driver
import { drizzle } from 'drizzle-orm/neon-http'; // MIGRATED: Supabase client → Drizzle ORM
import { eq, and } from 'drizzle-orm'; // MIGRATED: Drizzle ORM operators
// TODO: Define Drizzle schema for table definitions (see drizzle-orm docs)
// TODO: Import your todos table schema, e.g.: import { todos } from '@/db/schema';

type EditTodoProps = {
    open: boolean;
    todoId: number;
    handleClose: () => void;
    refreshTodos: () => Promise<void>;
};

// MIGRATED: Supabase client instantiation → Neon + Drizzle
const sql = neon(process.env.DATABASE_URL!);
const db = drizzle(sql);

const EditTodoModal = (props: EditTodoProps) => {
    const {
        open, todoId, handleClose, refreshTodos,
    } = props;

    const [input, setInput] = useState<string>('');

    const { userId } = useAuth();

    const getTodo = async () => {
        // MIGRATED: Supabase query builder → Drizzle ORM
        // TODO: Manual review needed — verify Drizzle schema matches Supabase table
        try {
            const [row] = await db
                .select()
                .from(todos) // TODO: Replace `todos` with your imported Drizzle schema table
                .where(eq(todos.id, todoId)) // MIGRATED: .eq('id', todoId) → eq(todos.id, todoId)
                .limit(1); // MIGRATED: .single() → destructured first element with .limit(1)

            if (row) {
                setInput(row.text);
            }
        } catch (error) {
            // MIGRATED: Supabase { data, error } pattern → try/catch
            console.log((error as Error).message);
        }
    };

    useEffect(() => {
        getTodo();
    }, [todoId]);

    const UpdatedTodo = async () => {
        const currentTime = new Date().getTime();
        // MIGRATED: Supabase query builder → Drizzle ORM
        // TODO: Manual review needed — verify Drizzle schema matches Supabase table
        try {
            await db
                .update(todos) // TODO: Replace `todos` with your imported Drizzle schema table
                .set({ text: input, updated_at: currentTime }) // MIGRATED: .update({ ... }) → .set({ ... })
                .where(
                    and(
                        eq(todos.id, todoId), // MIGRATED: .eq('id', todoId) → eq(todos.id, todoId)
                        eq(todos.clerk_user_id, userId), // MIGRATED: .eq('clerk_user_id', userId) → eq(todos.clerk_user_id, userId)
                    ),
                );
        } catch (error) {
            // MIGRATED: Supabase { data, error } pattern → try/catch
            console.log((error as Error).message);
        }

        toast.success('Todo updated successfully.');
        handleClose();
        refreshTodos();
    };

    return (
        <Dialog
            open={open}
            onClose={handleClose}
            aria-describedby="alert-dialog-slide-description"
            fullWidth
        >
            <DialogTitle>Edit Card</DialogTitle>
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
                <Button variant="outlined" onClick={() => { UpdatedTodo(); }}>Save</Button>
            </DialogActions>
        </Dialog>
    );
};

export default EditTodoModal;