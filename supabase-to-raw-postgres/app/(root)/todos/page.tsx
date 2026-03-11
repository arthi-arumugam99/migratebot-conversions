'use client';

import React, { useState, useEffect } from 'react';
import { Grid, Container, Button } from '@mui/material';
import TodoList from '@/components/dragAndDrop/TodoList';
import { DndContext, useSensor, PointerSensor, DragEndEvent, useSensors } from '@dnd-kit/core';
import { useAuth } from '@clerk/nextjs';
import NewTodo from '@/components/dragAndDrop/CreateTodoModal';
import { SortableContext } from '@dnd-kit/sortable';
import { TodosType } from '@/utils/types/helper.types';

const cardTitles = [
    { index: 0, title: 'Unassigned' },
    { index: 1, title: 'Todo' },
    { index: 2, title: 'In Progress' },
    { index: 3, title: 'Done' }];

const TodosPage = () => {
    const [todos, setTodos] = useState<TodosType[]>([]);
    const [open, setOpen] = React.useState(false);

    // MIGRATED: Removed useSupabase hook — using fetch-based API calls to server endpoints instead
    const { userId } = useAuth();

    const pointerSensor = useSensor(PointerSensor, {
        activationConstraint: {
            delay: 250,
            tolerance: 5,
        },
    });

    const sensors = useSensors(pointerSensor);

    const getTodos = async () => {
        try {
            // MIGRATED: Supabase query builder → raw SQL (pg) via API route
            // Original: supabase.from('todos').select('*').order('updated_at', { ascending: false })
            // SQL: SELECT * FROM todos ORDER BY updated_at DESC
            const response = await fetch('/api/todos');
            if (!response.ok) {
                const { error } = await response.json();
                console.log(error);
                return;
            }
            const { rows } = await response.json();
            setTodos(rows as TodosType[]);
        } catch (err) {
            console.log(err);
        }
    };

    useEffect(() => {
        getTodos();
    }, []);

    const handleDragEnd = async (event: DragEndEvent) => {
        const { active, over } = event;

        if (!over || active.id === over.id) {
            return;
        }

        const newTodoLevel = cardTitles.findIndex((card) => card.title === over.id);

        if (newTodoLevel === -1) {
            return;
        }

        try {
            // MIGRATED: Supabase query builder → raw SQL (pg) via API route
            // Original: supabase.from('todos').update({ todo_level: newTodoLevel, updated_at: new Date().getTime() }).eq('id', active.id).eq('clerk_user_id', userId)
            // SQL: UPDATE todos SET todo_level = $1, updated_at = $2 WHERE id = $3 AND clerk_user_id = $4
            const response = await fetch(`/api/todos/${active.id}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    todo_level: newTodoLevel,
                    updated_at: new Date().getTime(),
                    clerk_user_id: userId,
                }),
            });

            if (!response.ok) {
                const { error } = await response.json();
                console.log(error);
                return;
            }

            getTodos();
        } catch (err) {
            console.log(err);
        }
    };

    const handleClose = () => {
        setOpen(false);
    };

    return (
        <>
            <Container maxWidth="xl">
                <Grid container spacing={2} sx={{ mt: 5, display: 'flex', justifyContent: 'center' }}>
                    <Grid item xs={12}>
                        <Button color="primary" onClick={() => { setOpen(true); }} variant="outlined">Create Todo</Button>
                    </Grid>
                    <DndContext sensors={sensors} onDragEnd={(event) => { handleDragEnd(event); }}>
                        <SortableContext items={todos.map((todo) => todo.id)}>
                            <Grid container spacing={2} sx={{ mt: 1, display: 'flex', justifyContent: 'center' }}>
                                {cardTitles.map((cardTitle) => (
                                    <TodoList
                                        key={cardTitle.index}
                                        level={cardTitle.title}
                                        todos={todos.filter((todo) => todo.todo_level === cardTitle.index)}
                                        refreshTodos={getTodos}
                                    />
                                ))}
                            </Grid>
                        </SortableContext>
                    </DndContext>
                </Grid>
            </Container>
            <NewTodo open={open} handleClose={handleClose} onActionFinish={getTodos} />
        </>
    );
};

export default TodosPage;