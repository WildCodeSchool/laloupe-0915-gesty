# SchoolCalendar\Component

## Scheduler component
Contains every components responsible for scheduling :
- Scheduler : for adding events (days off, significant periods)
- DateNow : for setting a date of day, testable, changeable for all the application
- Day : class meant to deal with everything related to a date on one day.
- Period : period of 2 dates, iterable.

Contains every components used by the SchoolCalendar\Bundle.
Can be used for any other needs, independantly from the bundle.


## PeriodProvider
Provide periods, dates to the scheduler
require the scheduler component.
Used by SchoolCalendar\Bundle
Offer interfaces that can be implemented by the application
for adding specific days off dates to the scheduler.
