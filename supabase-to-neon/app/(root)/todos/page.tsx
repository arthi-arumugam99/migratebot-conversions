'use client';

import React, { useState, useEffect } from 'react';
import { Grid, Container, Button } from '@mui/material';
import TodoList from '@/components/dragAndDrop/TodoList';
import { DndContext, useSensor, PointerSensor, DragEndEvent, useSensors } from '@dnd-kit/core';
import { useAuth } from '@clerk/nextjs';
import NewTodo from '@/components/dragAndDrop/CreateTodoModal';
import { SortableContext } from '@dnd-kit/sortable';
import { TodosType } from '@/utils/types/helper.types';
import { neon } from '@neondatabase/serverless'; // MIGRATED: replaced useSupabase hook with Neon serverless driver
import { drizzle } from 'drizzle-orm/neon-http'; // MIGRATED: Drizzle ORM for query building
import { eq, and, desc } from 'drizzle-orm'; // MIGRATED: Drizzle filter operators
// TODO: Define Drizzle schema for table definitions (see drizzle-orm docs)
// TODO: Import your todos table schema once defined, e.g.: import { todos as todosTable } from '@/db/schema';

const cardTitles = [
    { index: 0, title: 'Unassigned' },
    { index: 1, title: 'Todo' },
    { index: 2, title: 'In Progress' },
    { index: 3, title: 'Done' }];

const TodosPage = () => {
    const [todos, setTodos] = useState<TodosType[]>([]);
    const [open, setOpen] = React.useState(false);

    // MIGRATED: Supabase client replaced with Neon + Drizzle setup
    const sql = neon(process.env.NEXT_PUBLIC_DATABASE_URL!);
    const db = drizzle(sql);
    // TODO: Manual review needed — NEXT_PUBLIC_DATABASE_URL exposes the connection string to the client;
    // consider moving all DB calls to API routes (app/api/) to keep DATABASE_URL server-side only

    const { userId } = useAuth();

    const pointerSensor = useSensor(PointerSensor, {
        activationConstraint: {
            delay: 250,
            tolerance: 5,
        },
    });

    const sensors = useSensors(pointerSensor);

    const getTodos = async () => {
        // MIGRATED: Supabase query builder → Drizzle ORM
        // TODO: Manual review needed — verify Drizzle schema matches Supabase table
        try {
            // TODO: Replace `todosTable` with your actual Drizzle schema table reference once defined
            // e.g.: const data = await db.select().from(todosTable).orderBy(desc(todosTable.updated_at));
            const data = await sql`SELECT * FROM todos ORDER BY updated_at DESC` as TodosType[];
            setTodos(data);
        } catch (err) {
            console.log((err as Error).message);
        }
    };

    useEffect(() => {
        getTodos();
    }, []); // MIGRATED: removed `supabase` dependency — Neon client is stable

    const handleDragEnd = async (event: DragEndEvent) => {
        const { active, over } = event;

        if (!over || active.id === over.id) {
            return;
        }

        const newTodoLevel = cardTitles.findIndex((card) => card.title === over.id);

        if (newTodoLevel === -1) {
            return;
        }

        // MIGRATED: Supabase .update().eq().eq() → Drizzle db.update().set().where(and(eq(), eq()))
        // TODO: Manual review needed — verify Drizzle schema matches Supabase table
        try {
            // TODO: Replace raw SQL with Drizzle ORM once schema is defined, e.g.:
            // await db.update(todosTable)
            //   .set({ todo_level: newTodoLevel, updated_at: new Date().getTime() })
            //   .where(and(eq(todosTable.id, active.id as string), eq(todosTable.clerk_user_id, userId!)));
            await sql`
                UPDATE todos
                SET todo_level = ${newTodoLevel}, updated_at = ${new Date().getTime()}
                WHERE id = ${active.id as string}
                AND clerk_user_id = ${userId}
            `;
            getTodos();
        } catch (err) {
            console.log((err as Error).message);
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