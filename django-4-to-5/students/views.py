from django.http import HttpResponseRedirect
from django.shortcuts import render
from django.urls import reverse

from .models import Student
from .forms import StudentForm


# Create your views here.
# TODO: Consider making this view async (Django 5.0 async improvements)
# This view performs a database query (Student.objects.all()) that could benefit from async execution.
# To migrate: change `def index` to `async def index` and use `await Student.objects.all()` with an async ORM method.
# Note: Ensure all middleware in settings.py supports async (check for non-async-compatible middleware).
def index(request):
  return render(request, 'students/index.html', {
    'students': Student.objects.all()
  })


def view_student(request, id):
  return HttpResponseRedirect(reverse('index'))


# TODO: Consider making this view async (Django 5.0 async improvements)
# This view performs database I/O (Student.save()) that could benefit from async execution.
# To migrate: change `def add` to `async def add` and use `await new_student.asave()` (Django 4.1+ async ORM).
# Note: Ensure all middleware in settings.py supports async (check for non-async-compatible middleware).
def add(request):
  if request.method == 'POST':
    form = StudentForm(request.POST)
    if form.is_valid():
      new_student_number = form.cleaned_data['student_number']
      new_first_name = form.cleaned_data['first_name']
      new_last_name = form.cleaned_data['last_name']
      new_email = form.cleaned_data['email']
      new_field_of_study = form.cleaned_data['field_of_study']
      new_gpa = form.cleaned_data['gpa']

      new_student = Student(
        student_number=new_student_number,
        first_name=new_first_name,
        last_name=new_last_name,
        email=new_email,
        field_of_study=new_field_of_study,
        gpa=new_gpa
      )
      new_student.save()
      return render(request, 'students/add.html', {
        'form': StudentForm(),
        'success': True
      })
  else:
    form = StudentForm()
  return render(request, 'students/add.html', {
    'form': StudentForm()
  })


# TODO: Consider making this view async (Django 5.0 async improvements)
# This view performs database I/O (Student.objects.get(), form.save()) that could benefit from async execution.
# To migrate: change `def edit` to `async def edit` and use `await Student.objects.aget(pk=id)` and `await form.asave()`.
# Note: Ensure all middleware in settings.py supports async (check for non-async-compatible middleware).
def edit(request, id):
  if request.method == 'POST':
    student = Student.objects.get(pk=id)
    form = StudentForm(request.POST, instance=student)
    if form.is_valid():
      form.save()
      return render(request, 'students/edit.html', {
        'form': form,
        'success': True
      })
  else:
    student = Student.objects.get(pk=id)
    form = StudentForm(instance=student)
  return render(request, 'students/edit.html', {
    'form': form
  })


# TODO: Consider making this view async (Django 5.0 async improvements)
# This view performs database I/O (Student.objects.get(), student.delete()) that could benefit from async execution.
# To migrate: change `def delete` to `async def delete` and use `await Student.objects.aget(pk=id)` and `await student.adelete()`.
# Note: Ensure all middleware in settings.py supports async (check for non-async-compatible middleware).
def delete(request, id):
  if request.method == 'POST':
    student = Student.objects.get(pk=id)
    student.delete()
  return HttpResponseRedirect(reverse('index'))