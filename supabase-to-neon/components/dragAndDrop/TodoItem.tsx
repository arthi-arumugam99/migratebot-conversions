/* eslint-disable no-nested-ternary */
import React, { useState } from 'react';
import { Card, CardContent, Typography, Chip, Divider, IconButton, Box } from '@mui/material';
import AccessTimeIcon from '@mui/icons-material/AccessTime';
import { CSS } from '@dnd-kit/utilities';
import { format, formatDistanceToNow } from 'date-fns';
import { useDraggable } from '@dnd-kit/core';
import EditIcon from '@mui/icons-material/Edit';
import DeleteIcon from '@mui/icons-material/Delete';
import { TodosType } from '@/utils/types/helper.types';
import { useAuth } from '@clerk/nextjs';
import toast from 'react-hot-toast';
import { eq, and } from 'drizzle-orm'; // MIGRATED: Supabase filter methods → Drizzle operators
import { db } from '@/lib/db'; // MIGRATED: Supabase client → Drizzle db instance
import { todos } from '@/lib/schema'; // TODO: Define Drizzle schema for table definitions (see drizzle-orm docs)
import EditTodoModal from './EditTodoModal';

type TodoItemProps = {
    todo: TodosType
    refreshTodos: () => Promise<void>;
};

const TodoItem = (props: TodoItemProps) => {
    const { todo, refreshTodos } = props;

    const [editTodoShow, setEditTodoShow] = useState<boolean>(false);

    const { userId } = useAuth();

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        active,
    } = useDraggable({ id: todo.id });

    const handleDelete = async (event: React.MouseEvent<HTMLButtonElement>, id: number) => {
        event.stopPropagation();
        // MIGRATED: .from('todos').delete().match({ id, clerk_user_id }) → db.delete(todos).where(and(eq(...), eq(...)))
        try {
            await db
                .delete(todos)
                .where(and(eq(todos.id, id), eq(todos.clerk_user_id, userId!)));
        } catch (error) {
            // MIGRATED: Supabase { data, error } pattern → try/catch
            console.log((error as Error).message);
        }

        toast.success('Todo deleted successfully.');
        refreshTodos();
    };

    const handleEditModal = (event: React.MouseEvent<HTMLButtonElement>) => {
        event.stopPropagation();
        setEditTodoShow(true);
    };

    const handleEditTodoClose = () => {
        setEditTodoShow(false);
    };

    return (
        <div style={{ position: 'relative' }}>
            <div
                style={{
                    transform: CSS.Translate.toString(transform),
                    opacity: active ? (active.id === todo.id ? 1 : 0.5) : 1,
                    borderRadius: '8px',
                    position: 'relative',
                    cursor: 'grab',
                }}
                ref={setNodeRef}
                {...attributes}
                {...listeners}
            >
                <Card sx={{ minWidth: 275, boxShadow: '0 0 5px 0' }}>
                    <CardContent>
                        <Typography sx={{ mb: 1 }}>{todo.text}</Typography>
                        <Divider />
                        <Box sx={{ display: 'flex', justifyContent: 'space-between' }}>
                            <Chip
                                sx={{ mt: 2 }}
                                icon={<AccessTimeIcon />}
                                label={`${formatDistanceToNow(new Date(todo.updated_at))} ago, ${format(new Date(todo.updated_at), 'HH.mm')}`}
                                color="warning"
                                variant="outlined"
                                size="small"
                            />
                            <Box>
                                <IconButton edge="end" onClick={(event) => { handleEditModal(event); }}>
                                    <EditIcon color="success" fontSize="small" />
                                </IconButton>
                                <IconButton edge="end" onClick={(event) => { handleDelete(event, todo.id); }}>
                                    <DeleteIcon color="error" fontSize="small" />
                                </IconButton>
                            </Box>
                        </Box>
                    </CardContent>
                </Card>
            </div>
            <EditTodoModal
                open={editTodoShow}
                handleClose={handleEditTodoClose}
                todoId={todo.id}
                refreshTodos={refreshTodos}
            />
        </div>
    );
};

export default TodoItem;