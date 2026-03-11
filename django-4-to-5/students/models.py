from django.db import models


# Create your models here.
class Student(models.Model):
  student_number = models.PositiveIntegerField()
  first_name = models.CharField(max_length=50)
  last_name = models.CharField(max_length=50)
  email = models.EmailField(max_length=100)
  field_of_study = models.CharField(max_length=50)
  gpa = models.FloatField()  # TODO: Consider using db_default for database-level defaults (Django 5.0+)

  def __str__(self):
    return f'Student: {self.first_name} {self.last_name}'

  # TODO: Consider GeneratedField for this computed value (Django 5.0+)
  # full_name could be a GeneratedField combining first_name and last_name