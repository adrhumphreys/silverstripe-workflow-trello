import Injector from "lib/Injector";
import WorkflowButton from "../components/WorkflowButton";

export default () => {
  Injector.transform("workflow-trello", (updater) => {
    updater.component(
      "WorkflowButton",
      () => (props) => <WorkflowButton {...props} />,
      "TrelloWorkflowButton"
    );
  });
};
