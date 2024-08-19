using UnitTestCaseDemo.Controllers;
using UnitTestCaseDemo.Services;
using UnitTestCaseDemo.Model;
using Microsoft.AspNetCore.Mvc;
using Xunit.Abstractions;
using Xunit;

namespace UnitTestCaseDemo.Test
{
    public class EmployeeControllerTest
    {
        EmployeeController _controller;
        IEmployeeService _service;
        ITestOutputHelper _output;
        public EmployeeControllerTest(ITestOutputHelper output)
        {
            _service = new EmployeeService();
            _controller = new EmployeeController(_service);
            _output = output;
        }

        [Fact]
        public void GetAll_Employee_Success()
        {
            //Arrange

            //Act
            var result = _controller.Get();
            var resultType = result as OkObjectResult;
            var resultList = resultType.Value as List<Employee>;
            foreach (var resultx in resultList)
            {
                var employee = resultx as Employee;
                _output.WriteLine(employee.Name);
            }
            //Assert
            Assert.NotNull(result);
            Assert.IsType<List<Employee>>(resultType.Value);
            Assert.Equal(3, resultList.Count);
        }

        [Fact]
        public void GetById_Employee_Success()
        {
            //Arrange
            int valid_empid = 101;
            int invalid_empid = 110;

            //Act
            var errorResult = _controller.Get(invalid_empid);
            var successResult = _controller.Get(valid_empid);
            var successModel = successResult as OkObjectResult;
            var fetchedEmp = successModel.Value as Employee;

            //Assert
            Assert.IsType<OkObjectResult>(successResult);
            Assert.IsType<NotFoundResult>(errorResult);
            Assert.Equal(101, fetchedEmp.Id);

        }

        [Fact]
        public void Add_Employee_Success()
        {
            Employee employee = new Employee() { 
                Id = 105,
                Name = "Shane Warne",
                PhoneNo = "5555555555",
                EmailId = "shane@email.com"
            };

            var response = _controller.Post(employee);
            
            Assert.IsType<CreatedAtActionResult>(response);

            var createdEmp = response as CreatedAtActionResult;
            Assert.IsType<Employee>(createdEmp.Value);

            var employeeItem = createdEmp.Value as Employee;

            Assert.Equal("Shane Warne", employeeItem.Name);
            Assert.Equal("5555555555", employeeItem.PhoneNo);
            Assert.Equal("shane@email.com", employeeItem.EmailId);

            var result = _controller.Get();
            var resultType = result as OkObjectResult;
            var resultList = resultType.Value as List<Employee>;
            foreach (var resultx in resultList)
            {
                var employeex = resultx as Employee;
                _output.WriteLine(employeex.Name);
            }
        }


        [Fact]
        public void Delete_Employee_Success()
        {
            int valid_empid = 101;
            int invalid_empid = 110;

            var errorResult = _controller.Delete(invalid_empid);
            var successResult = _controller.Delete(valid_empid);

            Assert.IsType<OkObjectResult>(successResult);
            Assert.IsType<NotFoundObjectResult>(errorResult);
        }
    }
}