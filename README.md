# Laravel Recruitment Task

## Task Description

We ask you to create a simple REST API. It will be a component of a system for managing patients' medical documents, used by doctors. The system stores information about users (doctors), their patients, and documents from the patient's medical history. Each patient is assigned to only one doctor. Each document is assigned to only one patient.  
### Technical Requirements:
Docker
Laravel 11
A relational database of your choice
### Task Requirements:
As a user (doctor), I want to be able to:  
Add documents for a patient under my care
Document storage should be an asynchronous operation
The added document must be a PDF file no larger than a specified number of MB. By default, this should be 5MB, configurable via an environment variable
A user (doctor) can only manage documents for their own patients
### Notes:
There is no need to implement login and registration using bearer tokens. For the purpose of this task, use basic auth - the user's email and password. Only the implementation of patient document management is required. Sample records of patients and doctors should be included in the submitted solution.
## How to Build and Test the Application

To build the application, simply run the following command:
```sh
make start
```

To test the application, run:
```sh
make test
```

## Sample Data

Sample records of patients and doctors are included in the project as seeders (executed automatically when the application is built).

## Asynchronous Document Storage

The requirement for asynchronous document storage was likely a tricky question, as files cannot be passed to asynchronous jobs, and there's probably no reasonable workaround.
Therefore, the file is saved immediately, and in the asynchronous part, only the Document object with the path to the saved file is added.

## Additional Information

I hope I haven't forgotten anything, and if there are any uncertainties, please feel free to contact me - of course I will explain or discuss anything further.
