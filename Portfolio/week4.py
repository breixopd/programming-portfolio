# check if number between 0 and 100
def check(num):
    try:
        if num < 0 or num > 100:
            print("Number is not between 0 and 100")
            return False
        else:
            print("Number is between 0 and 100")
            return True
    except:
        print("Needs to be a number!")


# check(input("Enter a number between 0 and 100: "))


# return number of upper and lowercase letters in string
def count_letters(string):
    upper = 0
    lower = 0
    for i in string:
        if i.isupper():
            upper += 1
        elif i.islower():
            lower += 1
    print("Number of uppercase letters: " + str(upper))
    print("Number of lowercase letters: " + str(lower))


# count_letters("Hello World")


# greeting where first name letter is always uppercase
def greeting(name):
    print(f"Hello {name[0].upper()}{name[1:].lower()}")


# greeting("jOHN")


# remove last character of string
def remover(string):
    if len(string) <= 1:
        return string
    else:
        return string[:-1]


# print(remover("Hello"))


# convert centigrade to fahrenheit
def convert(method=None, temp=None):
    try:
        if method.lower() == "f":
            return (temp - 32) * 5.0 / 9.0
        elif method.lower() == "c":
            return (temp * 9.0 / 5.0) + 32
    except:
        return "Convert celsius to fahrenheit and vice-versa,\nmethod needs to be 'c' or 'f', example: convert('c', " \
               "100)\n'c' - Celsius\n'f' - Fahrenheit\n"

# print(convert("f", 38.4))


# get mean of temperatures input by user (uses conversion function because why not)
def meantemp(temp_format=None):
    temps = []
    while True:
        temp = input("Enter a temperature (ENTER to quit): ")
        if temp.lower() == "":
            break
        else:
            temps.append(float(temp))
    if temp_format.lower() == "c":
        print(f"\nMean temperature: {round(sum(temps) / len(temps), 2)}")
        print(f"Maximum temperature: {max(temps)}")
        print(f"Minimum temperature: {min(temps)}")
        print("----- Fahrenheit -----")
        print(f"Mean temperature: {round(convert('c', sum(temps) / len(temps)), 2)}")
        print(f"Maximum temperature: {convert('c', max(temps))}")
        print(f"Minimum temperature: {convert('c', min(temps))}")
    elif temp_format.lower() == "f":
        print(f"\nMean temperature: {round(convert('f', sum(temps) / len(temps)), 2)}")
        print(f"Maximum temperature: {convert('f', max(temps))}")
        print(f"Minimum temperature: {convert('f', min(temps))}")
        print("----- Celsius -----")
        print(f"Mean temperature: {round(sum(temps) / len(temps), 2)}")
        print(f"Maximum temperature: {max(temps)}")
        print(f"Minimum temperature: {min(temps)}")
    if temp_format is None:
        print("You need to specify a temperature format, example: meantemp('c')\n'c' - Celsius\n'f' - Fahrenheit")


# meantemp("c")
