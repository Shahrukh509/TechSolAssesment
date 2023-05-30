
Code to refactor
=================
1) app/Http/Controllers/BookingController.php
2) app/Repository/BookingRepository.php

----------------------------

## Thoughts About Code:

1. I have realized in the previous code there have been used unnecessary variables (surely which causes memory allocation without any solid reason).
2. Used unwanted spaces and code was unindented.
3. Unnecessary ifs and elseifs.
4. variable naming is not meaningful.
5. no try and catch block.
6. no log debugbar used.
7. object initializing repeatedly.


## Amazing Code:

 1. To me good code is something that uses relevant methods name and same goes for variable.
 2. Instead of object initializing again and again just initialize it first.
 3. Using Repository pattern is good and SOLID principle should apply when you are coding.
 4. Make your code short and cleaner make private methods whenever you are injecting or updating data in db.
 5. use try catch block.
 6. Use depedency injection where needed.
 7. Every controller should performing single and relevant task as defined in SOLID principle.
 8. Use Early returns.
 9. Break long code into small methods to make it look cleaner to other programmers.
 10. Use comments at every step.
 
##  OK Code: 
 
 1. Not following solid priciple.
 2. Not using independency injection.
 3. donot using foreach and other loops for everytask.
 
## Terrible Code

 1. Initializing object everytime whenever calling method or nested method.
 2. Using variable with no or less meaning.
 3. Allocate memory for small information in variable.
 4. No debugging and logs.
 5. Not validating request.



