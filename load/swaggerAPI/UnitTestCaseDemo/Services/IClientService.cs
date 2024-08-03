using UnitTestCaseDemo.Model;

namespace UnitTestCaseDemo.Services
{
    public interface IClientService
    {
        List<Client> GetAll();
        Client GetById(int id);
        Client Add(Client employee);
        bool Delete(int id);
    }
}
